import Splide from '@splidejs/splide'
import Alpine from 'alpinejs'

window.Alpine = Alpine

// search
window.searchData = () => {
  return {
    controller: new AbortController(),
    searching: false,
    result: [],
    open: false,
    t: '',
    init() {
      window.addEventListener('click', e => {
        if (this.open && !e.target.classList.contains('search')) {
          this.open = false
        }
      })
    },
    empty() {
      this.open = false
      this.result = []
      this.t = ''
    },
    async search(e) {
      const t = e.target.value
      if (this.searching) {
        this.controller.abort()
        if (t.length === 0) {
          this.empty()
          return
        } else {
          this.controller = new AbortController()
        }
      }
      this.searching = true
      fetch(`/wp-json/wp/v2/product/?search=${t}`, {signal: this.controller.signal})
        .then(res => {
          this.searching = false
          return res.json()
        })
        .then(j => {
          this.result = j
          this.open = true
        })
    },
    async makeRequest() {

    }
  }
}

Alpine.start()

document.addEventListener( 'DOMContentLoaded', function() {
  const splide = new Splide( '.splide', {
    type: 'loop',
    autoplay: true,
  } );
  splide.mount();
} );