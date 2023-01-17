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
  const slider = new Splide('.splide-slider', {
    type: 'loop',
    autoplay: true,
  } )
  slider.mount()

  const categories = new Splide('.splide-categories', {
    type: 'loop',
    autoplay: true,
    gap: '.5rem',
    pagination: false,
    perPage: 1,
    perMove: 1,
    mediaQuery: 'min',
    breakpoints: {
      576: {
        perPage: 2,
      },
      992: {
        perPage: 3,
      },
      1200: {
        perPage: 4,
      },
    }
  })
  categories.mount()

  const products = new Splide('.splide-products', {
    type: 'loop',
    perPage: 1,
    gap: '.5rem',
    pagination: false,
    perMove: 1,
    mediaQuery: 'min',
    breakpoints: {
      576: {
        perPage: 2,
      },
      992: {
        perPage: 3,
      },
      1200: {
        perPage: 4,
      },
      1400: {
        perPage: 5,
      }
    }
  })
  products.mount()
});