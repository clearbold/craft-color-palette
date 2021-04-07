const Configurator = {
  data() {
    return {

      collections: [
        {
          'id': uuidv4(),
          'name': 'New Collection',
          'handle': 'newCollection',
          'themes': [
            {
              'id': uuidv4(),
              'name': 'New Theme',
              'handle': 'newTheme',
              'colors': []
            }
          ]
        }
      ],
      initialCollections: [],
      showFields: false,

      selectedCollection: 0,
      selectedTheme: 0,

      showModal: false,
      showModalDelete: true,
      modalType: 'collection',
      modalTargetIndex: 0,
      modalIndex: 0,
      modalNew: false,
      modalName: '',
      modalHandle: '',

      newColor: false,

      drag: false,

      shouldPrevent: false,

      checkEditState: (event) => {
        if (JSON.stringify(this.collections) !== JSON.stringify(this.initialCollections)) {
          event.preventDefault()
          event.returnValue = ''
        }
      }
    }
  },
  computed: {
    newHandle: {
      get() {
        this.modalHandle = this.camelize(this.modalName)
        return this.camelize(this.modalName)
      },
      set(newValue) {
        this.modalHandle = newValue
      }
    },
    newColorHandle: {
      get() {
        console.log('get')
        let colorsArray = this.collections[this.selectedCollection].themes[this.selectedTheme].colors
        let colorsLength = colorsArray.length
        let handle = this.kebab(
          // Generate this handle from the new name
          this.collections[this.selectedCollection].themes[this.selectedTheme].colors[colorsLength - 1].name
        )
        // If this new handle matches another handle in this theme, we have to change it
        for (let i = 0; i < this.collections[this.selectedCollection].themes[0].colors.length-1; i++) {
          let color = this.collections[this.selectedCollection].themes[0].colors[i]
          if (color.handle == handle)
            handle = handle + '-1'
        }
        // Add this color to the other themes in this collection
        for (let i = 0; i < this.collections[this.selectedCollection].themes.length; i++) {
          this.collections[this.selectedCollection].themes[i].colors[colorsLength - 1].handle = handle
        }
        return handle
      },
      set(newValue) {
        let colorsLength = this.collections[this.selectedCollection].themes[this.selectedTheme].colors.length
        // this.collections[this.selectedCollection].themes[this.selectedTheme].colors[colorsLength - 1].handle = newValue
        for (let i = 0; i < this.collections[this.selectedCollection].themes.length; i++) {
          this.collections[this.selectedCollection].themes[i].colors[colorsLength - 1].handle = newValue
        }
      }
    }
  },
  mounted() {
    axios.get('/index.php?p=actions/colorpalette/color-palette/index', {})
      .then(({ data }) => {
        if (data.palette.collections.length > 0) {
          this.collections = data.palette.collections
          this.initialCollections = JSON.parse(JSON.stringify(this.collections))
        }
        this.showFields = true
      })

    this._keyListener = function(e) {
      if (e.key === "s" && (e.ctrlKey || e.metaKey)) {
        e.preventDefault(); // present "Save Page" from getting triggered.
        this.savePalette()
      }
    }
    document.addEventListener('keydown', this._keyListener.bind(this))

    window.addEventListener('beforeunload', this.checkEditState, {capture: true})
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this._keyListener)
  },
  methods: {
    setCollectionName(index) {
      this.modalType = 'collection'
      this.modalIndex = index
      this.modalNew = false
      this.modalName = this.collections[index].name
      this.modalHandle = this.collections[index].handle
      this.modalEdit(this.modalType)
    },
    setThemeName(index) {
      this.modalType = 'theme'
      this.modalIndex = index
      this.modalNew = false
      this.modalName = this.collections[this.selectedCollection].themes[index].name
      this.modalHandle = this.collections[this.selectedCollection].themes[index].handle
      this.modalEdit(this.modalType)
    },
    modalClose() {
      let targetArray = (this.modalType == 'theme') ? this.collections[this.selectedCollection].themes : this.collections
      if (targetArray[this.modalIndex].name === undefined || targetArray[this.modalIndex].name === null) {
        if (this.modalType == 'collection') {
          this.selectedCollection = (this.modalIndex - 1 > 0) ? this.modalIndex - 1 : 0
          this.collections[this.modalIndex].themes.splice(0, 1)
          this.collections.splice(this.modalIndex, 1)
        }
        else {
          this.selectedTheme = (this.modalIndex - 1 > 0) ? this.modalIndex - 1 : 0
          this.collections[this.selectedCollection].themes.splice(this.modalIndex, 1)
        }
      }
      this.showModal = false
    },
    modalOpen() {
      this.showModal = true
      Vue.nextTick(() => {
        document.getElementById('modal-item-name').focus()
      })
    },
    modalCreate() {
      this.showModalDelete = false
      let targetArray = (this.modalType == 'theme') ? this.collections[this.selectedCollection].themes : this.collections
      this.modalIndex = targetArray.length - 1
      this.modalName = ''
      this.modalHandle = ''
      this.modalOpen()
    },
    modalEdit() {
      if (
        (this.modalType === 'collection' && this.collections.length > 1)
        || (this.modalType === 'theme' && this.collections[this.selectedCollection].themes.length > 1)
      )
        this.showModalDelete = true
      else
        this.showModalDelete = false
      this.modalOpen()
    },
    modalSave() {
      if (this.modalName.length && this.modalHandle.length) {
        let matchedHandle = false
        let modalIndex = this.modalIndex
        let modalHandle = this.modalHandle
        if (this.modalType === 'collection') {
          this.collections.forEach(function(collection, index) {
            if (collection.handle === modalHandle && index !== modalIndex) {
              matchedHandle = true
            }
          })
          this.collections[this.modalIndex].name = this.modalName
          this.collections[this.modalIndex].handle = !matchedHandle ? this.modalHandle : this.modalHandle + '-1'
        } else {
          this.collections[this.selectedCollection].themes.forEach(function(theme, index) {
            if (theme.handle === modalHandle && index !== modalIndex) {
              matchedHandle = true
            }
          })
          this.collections[this.selectedCollection].themes[this.modalIndex].name = this.modalName
          this.collections[this.selectedCollection].themes[this.modalIndex].handle = !matchedHandle ? this.modalHandle : this.modalHandle + '-1'
        }
        this.modalClose()
      }
    },
    modalDelete() {
      if (this.modalType == 'collection') {
        this.selectedCollection = this.modalIndex > 1 ? this.modalIndex - 1 : 0
        this.collections.splice(this.modalIndex, 1)
        this.modalIndex = this.selectedCollection
      } else {
        this.selectedTheme = this.modalIndex > 1 ? this.modalIndex - 1 : 0
        this.collections[this.selectedCollection].themes.splice(this.modalIndex, 1)
        this.modalIndex = this.selectedTheme
      }
      this.modalClose()
    },
    selectCollection(index) {
      if (index != this.selectedCollection)
      {
        this.themes = this.collections[index].themes
        this.selectedTheme = 0
      }
      this.selectedCollection = index
    },
    selectTheme(index) {
      if (index != this.selectedTheme)
      {
        this.colors = this.collections[this.selectedCollection].themes[index].colors
      }
      this.selectedTheme = index
    },
    createCollection() {
      this.modalType = 'collection'
      this.modalNew = true
      this.collections.push(
        {
          'id': uuidv4(),
          'themes': [
            {
              'id': uuidv4(),
              'name': 'New Theme',
              'handle': 'newTheme',
              'colors': []
            }
          ]
        }
      )
      this.selectedCollection = this.collections.length - 1
      this.selectedTheme = 0
      this.modalCreate()
    },
    createTheme() {
      this.modalType = 'theme'
      this.modalNew = true
      let newColors = []
      this.collections[this.selectedCollection].themes[0].colors.forEach(color => {
        newColors.push({
          'id': uuidv4(),
          'name': color.name,
          'handle': color.handle,
          'color': '#000000',
          'alpha': 1
        })
      })
      this.collections[this.selectedCollection].themes.push(
        {
          'id': uuidv4(),
          'colors': newColors
        }
      )
      this.selectedTheme = this.collections[this.selectedCollection].themes.length - 1
      this.modalCreate()
    },
    createColor() {
      this.newColor = true
      this.collections[this.selectedCollection].themes.forEach(theme => {
        theme.colors.push({
          'id': uuidv4(),
          'name': '',
          'handle': '',
          'color': '#000000',
          'alpha': 1
        })
      })
      Vue.nextTick(() => {
        let colorFields = document.getElementsByClassName('color-name')
        let colorField = colorFields[colorFields.length - 1].getElementsByTagName('input')[0]
        colorField.focus()
      })
    },
    resetNewColor(index) {
      this.newColor = false
    },
    deleteColor(index) {
      this.collections[this.selectedCollection].themes.forEach(theme => {
        theme.colors.splice(index, 1)
      })
    },
    savePalette() {
      window.removeEventListener('beforeunload', this.checkEditState, {capture: true})

      let mainForm = document.getElementById('main-form')
      let field = document.getElementById('palette-field')
      field.value = JSON.stringify(this.collections)
      mainForm.submit()
    },
    updateColorNames(index) {
      // console.log('update color name ' + index)
      for (let i = 1; i < this.collections[this.selectedCollection].themes.length; i++) {
        this.collections[this.selectedCollection].themes[i].colors[index].name = this.collections[this.selectedCollection].themes[0].colors[index].name
        // console.log(this.collections[this.selectedCollection].themes[i].colors[index])
      }
    },
    updateColorHandles(index) {
      // console.log('update color handle ' + index)
      let thisColor = this.collections[this.selectedCollection].themes[0].colors[index]
      for (let i = 0; i < this.collections[this.selectedCollection].themes[0].colors.length; i++) {
        let color = this.collections[this.selectedCollection].themes[0].colors[i]
        if (color.handle == thisColor.handle && i != index)
          thisColor.handle = thisColor.handle + '-1'
      }
      for (let i = 1; i < this.collections[this.selectedCollection].themes.length; i++) {
        this.collections[this.selectedCollection].themes[i].colors[index].handle = this.collections[this.selectedCollection].themes[0].colors[index].handle
      }
    },
    sortColors() {
      for (let i = 1; i < this.collections[this.selectedCollection].themes.length; i++) {
        let newColors = []
        this.collections[this.selectedCollection].themes[0].colors.forEach(color => {
          // console.log(this.collections[this.selectedCollection].themes[i].colors)
          this.collections[this.selectedCollection].themes[i].colors.forEach(themeColor => {
            // console.log('main color: ' + color.handle)
            // console.log(this.collections[this.selectedCollection].themes[i].name)
            // console.log('current theme color: ' + themeColor.handle)
            if (color.handle === themeColor.handle)
              newColors.push(themeColor)
          })
          // console.log(newColors)
        })
        this.collections[this.selectedCollection].themes[i].colors = newColors
      }
    },
    kebab(str) {
      // https://www.w3resource.com/javascript-exercises/fundamental/javascript-fundamental-exercise-123.php
      return str.length ? str
        .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
        .map(x => x.toLowerCase())
        .join('-') : ''
    },
    camelize(str) {
      // https://stackoverflow.com/a/2970667/1263204
      return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
        if (+match === 0) return "" // or if (/\s+/.test(match)) for white spaces
        return index === 0 ? match.toLowerCase() : match.toUpperCase()
      })
    },
    isDarkColor(color) {
      // https://awik.io/determine-color-bright-dark-using-javascript/

      // Variables for red, green, blue values
      let r, g, b, hsp = 0;

      // Check the format of the color, HEX or RGB?
      if (color.match(/^rgb/)) {
        // If RGB --> store the red, green, blue values in separate variables
        color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/)

        r = color[1]
        g = color[2]
        b = color[3]
      }
      else {
        // If hex --> Convert it to RGB: http://gist.github.com/983661
        color = +("0x" + color.slice(1).replace(
          color.length < 5 && /./g, '$&$&'))

        r = color >> 16
        g = color >> 8 & 255
        b = color & 255
      }

      // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
      hsp = Math.sqrt(
        0.299 * (r * r) +
        0.587 * (g * g) +
        0.114 * (b * b)
      )

      // Using the HSP value, determine whether the color is light or dark
      if (hsp > 127.5)
        return false
      else
        return true
    }
  },
  delimiters: ['${', '}']
}
Vue.createApp(Configurator).component('draggable', vuedraggable).mount('main')