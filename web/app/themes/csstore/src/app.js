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

// mini cart
window.miniCartData = () => {
  return {
    open: false,
    openMiniCart() {
      this.open = true
    },
    closeMiniCart() {
      this.open = false
    }
  }
}

Alpine.start()

document.addEventListener( 'DOMContentLoaded', function() {
  const s = document.querySelector('.splide-slider')
  if (s) {
    const slider = new Splide(s, {
      type: 'loop',
      autoplay: true,
    } )
    slider.mount()
  }

  const c = document.querySelector('.splide-categories')
  if (s) {
    const categories = new Splide(c, {
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
  }

  const productsRows = document.querySelectorAll('.splide-products')
  productsRows.forEach(r => {
    const products = new Splide(r, {
      type: 'loop',
      perPage: 2,
      gap: '.25rem',
      pagination: false,
      perMove: 1,
      mediaQuery: 'min',
      breakpoints: {
        576: {
          perPage: 2,
          gap: '.5rem',
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
  })

  const t = document.querySelector('.splide-tiles')
  if (t) {
    const tiles = new Splide('.splide-tiles', {
      type: 'loop',
      perPage: 2,
      pagination: false,
      mediaQuery: 'min',
      breakpoints: {
        992: {
          perPage: 3,
        },
      }
    })
    tiles.mount()
  }
});