<template>
  <div class="relative inline-block text-left">
    <button type="button" @click="show = true" class="flex items-center focus:outline-none">
      <slot />
    </button>
    <teleport v-if="show" to="#dropdown">
      <div>
        <div style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 99998; background: black; opacity: 0.2" @click="show = false" />
        <div ref="dropdown" style="position: absolute; z-index: 99999" @click.stop="show = !autoClose">
          <slot name="dropdown" />
        </div>
      </div>
    </teleport>
  </div>
</template>

<script>
import { createPopper } from '@popperjs/core'

export default {
  props: {
    placement: {
      type: String,
      default: 'bottom-end',
    },
    autoClose: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      show: false,
    }
  },
  methods: {
    handleEscape(e) {
      if (e.key === 'Escape') {
        this.show = false
      }
    },
    handleOutsideClick(e) {
      if (this.show && this.$el && !this.$el.contains(e.target) && 
          this.$refs.dropdown && !this.$refs.dropdown.contains(e.target)) {
        this.show = false
      }
    }
  },
  watch: {
    show(show) {
      if (show) {
        this.$nextTick(() => {
          this.popper = createPopper(this.$el, this.$refs.dropdown, {
            placement: this.placement,
            modifiers: [
              {
                name: 'preventOverflow',
                options: {
                  altBoundary: true,
                },
              },
            ],
          })
        })
      } else if (this.popper) {
        setTimeout(() => this.popper.destroy(), 100)
      }
    },
  },
  mounted() {
    document.addEventListener('keydown', this.handleEscape)
    document.addEventListener('click', this.handleOutsideClick)
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleEscape)
    document.removeEventListener('click', this.handleOutsideClick)
    
    if (this.popper) {
      this.popper.destroy()
    }
  }
}
</script>
