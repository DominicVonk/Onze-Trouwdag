export default function(name, hook) {
    document.querySelector('.modal[id="'+hook+'"]').classList.add('show');
    document.querySelector('.modal[id="'+hook+'"]').onclick = function() {
        document.querySelector('.modal[id="'+hook+'"]').classList.remove('show');
        document.querySelector('.modal[id="'+hook+'"]').querySelector('.modal__body').onclick = null;
        document.querySelector('.modal[id="'+hook+'"]').onclick = null; 
        if (document.querySelector('.modal[id="'+hook+'"]').getAttribute('data-on-close')) {
            window[document.querySelector('.modal[id="'+hook+'"]').getAttribute('data-on-close')]()
        }
    }
    document.querySelector('.modal[id="'+hook+'"]').querySelector('.modal__body').onclick = function(e) {
        e.stopPropagation();
    } 
    if (document.querySelector('.modal[id="'+hook+'"]').getAttribute('data-on-open')) {
        window[document.querySelector('.modal[id="'+hook+'"]').getAttribute('data-on-open')]()
    }
}