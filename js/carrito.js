/**
 * Saskiaren kudeatzailea AJAX bidez
 * Produktuak gehitzeko/ezabatzeko aukera ematen du orria berritu gabe
 * Kontagailua automatikoki eguneratzen du
 */

class CarritoManager {
    constructor(apiUrl = '/denda_sqlite/api/carrito_api.php') {
        this.apiUrl = apiUrl;
        this.init();
    }

    /**
     * Saskiaren kudeatzailea abiarazten du
     */
    init() {
        this.actualizarContador();
        this.enlazarBotones();
    }

    /**
     * Saskiaren uneko kontagailua zerbitzaritik lortzen du
     */
    actualizarContador() {
        fetch(this.apiUrl + '?action=get_count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.mostrarContador(data.count);
                }
            })
            .catch(error => console.error('Errorea kontagailua lortzean:', error));
    }

    /**
     * Kontagailua DOMean erakusten du
     */
    mostrarContador(count) {
        const badge = document.getElementById('carrito-contador');
        const link = document.getElementById('cart-link');

        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'block';

                // Esteka eguneratu existitzen bada eta ez bagaude eskariaren orrian
                if (link && !window.location.pathname.includes('eskaria.php')) {
                    link.href = 'eskaria.php';
                    link.onclick = null;
                    // Onclick atributua ezabatu (nabigatzaile zaharrentzat)
                    link.removeAttribute('onclick');
                }
            } else {
                badge.style.display = 'none';

                // Portatzea saskia hutsik dagoenean
                if (link && !window.location.pathname.includes('eskaria.php')) {
                    link.href = 'javascript:void(0);';
                    link.onclick = function () {
                        alert('Saskia hutsik dago. Produktuak gehitu katalogotik.');
                        return false;
                    };
                }
            }
        }
    }

    /**
     * Produktu bat saskira gehitzen du AJAX bidez
     */
    agregarProducto(id) {
        if (!id || id <= 0) {
            console.error('Produktuaren ID baliogabea');
            return false;
        }

        const formData = new FormData();
        formData.append('id', id);

        fetch(this.apiUrl + '?action=add', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.mostrarContador(data.count);
                    this.mostrarNotificacion('Produktua saskira gehitu da', 'success');
                } else {
                    this.mostrarNotificacion('Errorea: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Errorea produktua gehitzean:', error);
                this.mostrarNotificacion('Errorea eskaera prozesatzean', 'error');
            });

        return false; // Estekaren berezko portaera saihestu
    }

    /**
     * Produktu bat saskitik ezabatzen du AJAX bidez
     */
    eliminarProducto(id) {
        if (!id || id <= 0) {
            console.error('Produktuaren ID baliogabea');
            return false;
        }

        const formData = new FormData();
        formData.append('id', id);

        fetch(this.apiUrl + '?action=remove', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.mostrarContador(data.count);
                    // Saskiaren orrian bagaude, orria berritu
                    if (window.location.pathname.includes('eskaria.php')) {
                        location.reload();
                    }
                    this.mostrarNotificacion('Produktua ezabatu da', 'success');
                } else {
                    this.mostrarNotificacion('Errorea: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Errorea produktua ezabatzean:', error);
                this.mostrarNotificacion('Errorea eskaera prozesatzean', 'error');
            });

        return false;
    }

    /**
     * Produktu baten kopurua eguneratzen du AJAX bidez
     */
    actualizarCantidad(id, quantity) {
        if (!id || id <= 0) return false;
        if (quantity < 1) {
            return this.eliminarProducto(id);
        }

        const formData = new FormData();
        formData.append('id', id);
        formData.append('quantity', quantity);

        fetch(this.apiUrl + '?action=update_quantity', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.mostrarContador(data.count);
                    if (window.location.pathname.includes('eskaria.php')) {
                        location.reload();
                    }
                } else {
                    this.mostrarNotificacion('Errorea: ' + data.message, 'error');
                }
            })
            .catch(error => console.error('Errorea kopurua eguneratzean:', error));

        return false;
    }

    /**
     * Jakinarazpen mugikor bat erakusten du
     */
    mostrarNotificacion(mensaje, tipo = 'info') {
        const notif = document.createElement('div');
        notif.className = 'notificacion notificacion-' + tipo;
        notif.textContent = mensaje;
        notif.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background-color: ${tipo === 'success' ? '#4CAF50' : tipo === 'error' ? '#f44336' : '#2196F3'};
            color: white;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease-in-out;
        `;

        document.body.appendChild(notif);

        setTimeout(() => {
            notif.style.animation = 'slideOut 0.3s ease-in-out';
            setTimeout(() => notif.remove(), 300);
        }, 3000);
    }

    /**
     * Produktuen botoiak saskiaren funtzioekin lotzen ditu
     */
    enlazarBotones() {
        // "Gehitu saskira" botoiak
        document.querySelectorAll('.btn-saskira').forEach(btn => {
            btn.replaceWith(btn.cloneNode(true));
        });

        document.querySelectorAll('.btn-saskira').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                let id = null;
                const href = btn.getAttribute('href');
                if (href && href.includes('?add=')) {
                    id = parseInt(href.split('?add=')[1], 10);
                } else if (btn.dataset.productId) {
                    id = parseInt(btn.dataset.productId, 10);
                }

                if (id) {
                    this.agregarProducto(id);
                }
            });
        });

        // Saskitik ezabatzeko botoiak
        document.querySelectorAll('.btn-eliminar-carrito').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            btn.replaceWith(newBtn);
            newBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const id = parseInt(newBtn.dataset.productId, 10);
                if (id && confirm('Ziur zaude produktu hau ezabatu nahi duzula?')) {
                    this.eliminarProducto(id);
                }
            });
        });

        // Kopurua handitzeko/gutxitzeko botoiak (eskaria.php orrian)
        document.querySelectorAll('.btn-cantidad').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            btn.replaceWith(newBtn);
            newBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const id = parseInt(newBtn.dataset.productId, 10);
                const currentQty = parseInt(newBtn.dataset.currentQty, 10);
                const action = newBtn.dataset.action; // 'inc' edo 'dec'

                if (id) {
                    const newQty = (action === 'inc') ? currentQty + 1 : currentQty - 1;
                    this.actualizarCantidad(id, newQty);
                }
            });
        });
    }

    /**
     * Erosketa formularioa balioztatzen du
     */
    validarFormulario(formId) {
        const form = document.getElementById(formId);
        if (!form) return false;

        const campos = {
            izena: { minLength: 3, maxLength: 50, required: true },
            abizenak: { minLength: 3, maxLength: 50, required: true },
            helbidea: { minLength: 5, maxLength: 100, required: true },
            postapostala: { minLength: 5, maxLength: 10, required: true },
            hiria: { minLength: 2, maxLength: 50, required: true },
            telefono: { minLength: 9, maxLength: 20, required: true },
            email: { required: true }
        };

        let valido = true;
        const errores = {};

        for (const [campo, validacion] of Object.entries(campos)) {
            const input = form.elements[campo];
            if (!input) continue;

            const valor = input.value.trim();

            // Balioztatze orokorrak
            if (validacion.required && !valor) {
                errores[campo] = 'Eremu hau derrigorrezkoa da';
                valido = false;
            } else if (valor) {
                if (validacion.minLength && valor.length < validacion.minLength) {
                    errores[campo] = `Gutxienez ${validacion.minLength} karaktere`;
                    valido = false;
                }
                if (validacion.maxLength && valor.length > validacion.maxLength) {
                    errores[campo] = `Gehienez ${validacion.maxLength} karaktere`;
                    valido = false;
                }
            }

            // Balioztatze espezifikoak regex bidez
            if (valido && valor) {
                if (['izena', 'abizenak', 'hiria'].includes(campo)) {
                    if (/[0-9]/.test(valor)) {
                        errores[campo] = 'Ezin du zenbakirik izan';
                        valido = false;
                    }
                } else if (['postapostala', 'telefono'].includes(campo)) {
                    const soloNumeros = /^[0-9]+$/;
                    if (!soloNumeros.test(valor.replace(/[\s\-()]/g, ''))) {
                        errores[campo] = 'Zenbakiak soilik onartzen dira';
                        valido = false;
                    } else if (campo === 'telefono' && valor.replace(/[\s\-()]/g, '').length < 9) {
                        errores[campo] = 'Gutxienez 9 zenbaki';
                        valido = false;
                    }
                } else if (campo === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(valor)) {
                        errores[campo] = 'Email baliogabea';
                        valido = false;
                    }
                }
            }

            // Errorea eremuan erakutsi
            if (errores[campo]) {
                input.classList.add('error');
                const errorDiv = input.parentElement.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.textContent = errores[campo];
                }
            } else {
                input.classList.remove('error');
                const errorDiv = input.parentElement.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.textContent = '';
                }
            }
        }

        return valido;
    }

    /**
     * Sarrera-datuak denbora errealean garbitzen ditu (zenbakiak/letrak blokeatu)
     */
    configurarBloqueoCaracteres(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const camposTexto = ['izena', 'abizenak', 'hiria'];
        const camposNumericos = ['postapostala', 'telefono'];

        camposTexto.forEach(campo => {
            const input = form.elements[campo];
            if (input) {
                input.addEventListener('input', () => {
                    input.value = input.value.replace(/[0-9]/g, '');
                });
            }
        });

        camposNumericos.forEach(campo => {
            const input = form.elements[campo];
            if (input) {
                input.addEventListener('input', () => {
                    input.value = input.value.replace(/[^0-9]/g, '');
                });
            }
        });
    }
}

// Instantzia globala sortu DOMa prest dagoenean
document.addEventListener('DOMContentLoaded', function () {
    const manager = new CarritoManager('/denda_sqlite/api/carrito_api.php');
    window.carritos = manager;
    manager.configurarBloqueoCaracteres('formulario-compra');
});

// Animazio estiloak gehitu
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
    .error {
        border-color: #f44336 !important;
        background-color: #ffebee !important;
    }
    .error-message {
        color: #f44336;
        font-size: 0.85rem;
        margin-top: 4px;
        display: block;
    }
`;
document.head.appendChild(style);