/**
 * AgroStock - JavaScript Principal
 */

// Configuración global
const AGROSTOCK_CONFIG = {
    apiUrl: window.location.origin + '/api',
    currency: 'USD',
    language: 'es',
    debug: true
};

// Clase principal de la aplicación
class AgroStockApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupAjaxDefaults();
    }

    setupEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupNavigation();
            this.setupForms();
            this.setupModals();
            this.setupAnimations();
        });

        window.addEventListener('scroll', this.handleScroll.bind(this));
        window.addEventListener('resize', this.handleResize.bind(this));
    }

    setupNavigation() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');

        if (navbarToggler && navbarCollapse) {
            navbarToggler.addEventListener('click', () => {
                navbarCollapse.classList.toggle('show');
            });
        }

        this.setActiveNavigation();
    }

    setActiveNavigation() {
        const currentPath = window.location.pathname + window.location.search;
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    }

    setupForms() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            form.addEventListener('submit', this.validateForm.bind(this));
        });
    }

    validateForm(event) {
        const form = event.target;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                this.showFieldError(input, 'Este campo es requerido');
                isValid = false;
            } else {
                this.clearFieldError(input);
            }
        });

        if (!isValid) {
            event.preventDefault();
            this.showNotification('Por favor, corrige los errores en el formulario', 'error');
        }

        return isValid;
    }

    showFieldError(input, message) {
        const errorDiv = input.parentNode.querySelector('.field-error') || 
                        document.createElement('div');
        errorDiv.className = 'field-error text-danger mt-1';
        errorDiv.textContent = message;
        
        if (!input.parentNode.querySelector('.field-error')) {
            input.parentNode.appendChild(errorDiv);
        }
        
        input.classList.add('is-invalid');
    }

    clearFieldError(input) {
        const errorDiv = input.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
        input.classList.remove('is-invalid');
    }

    setupModals() {
        const modalTriggers = document.querySelectorAll('[data-modal]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.getAttribute('data-modal');
                this.openModal(modalId);
            });
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
        }
    }

    setupAnimations() {
        const animatedElements = document.querySelectorAll('[data-animate]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        });

        animatedElements.forEach(element => {
            observer.observe(element);
        });
    }

    handleScroll() {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        }
    }

    handleResize() {
        this.updateResponsiveElements();
    }

    updateResponsiveElements() {
        const isMobile = window.innerWidth < 768;
        document.body.classList.toggle('mobile', isMobile);
    }

    setupAjaxDefaults() {
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            this.hideNotification(notification);
        }, 5000);
        
        const closeButton = notification.querySelector('.notification-close');
        closeButton.addEventListener('click', () => {
            this.hideNotification(notification);
        });
    }

    hideNotification(notification) {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }

    formatCurrency(amount, currency = AGROSTOCK_CONFIG.currency) {
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: currency
        }).format(amount);
    }
}

// Clase para manejo de productos
class ProductManager {
    constructor() {
        this.cart = this.loadCart();
        this.setupEventListeners();
    }

    setupEventListeners() {
        document.addEventListener('click', (e) => {
            if (e.target.matches('.add-to-cart')) {
                e.preventDefault();
                const productId = e.target.getAttribute('data-product-id');
                const productName = e.target.getAttribute('data-product-name');
                const productPrice = parseFloat(e.target.getAttribute('data-product-price'));
                
                this.addToCart({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1
                });
            }
        });
    }

    addToCart(product) {
        const existingItem = this.cart.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += product.quantity;
        } else {
            this.cart.push(product);
        }
        
        this.saveCart();
        this.updateCartDisplay();
        this.showNotification(`${product.name} agregado al carrito`, 'success');
    }

    loadCart() {
        const cart = localStorage.getItem('agrostock_cart');
        return cart ? JSON.parse(cart) : [];
    }

    saveCart() {
        localStorage.setItem('agrostock_cart', JSON.stringify(this.cart));
    }

    updateCartDisplay() {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = this.getCartCount();
        }
    }

    getCartCount() {
        return this.cart.reduce((count, item) => count + item.quantity, 0);
    }

    showNotification(message, type) {
        if (window.agroStockApp) {
            window.agroStockApp.showNotification(message, type);
        }
    }
}

// Inicializar aplicación
document.addEventListener('DOMContentLoaded', () => {
    window.agroStockApp = new AgroStockApp();
    window.productManager = new ProductManager();
    
    if (AGROSTOCK_CONFIG.debug) {
        console.log('AgroStock App inicializada correctamente');
    }
});
