import Dropzone from "dropzone"

const imageField = document.querySelector('[name="image"]')

Dropzone.autoDiscover = false
const dropzone = new Dropzone('#dropzone', {
    dictDefaultMessage: 'Sube aquí tu imagen',
    acceptedFiles: '.png,.jpg,.jpeg,.gif',
    addRemoveLinks: true,
    dictRemoveFile: 'Borrar Archivo',
    maxFiles: 1,
    uploadMultiple: false,

    init: function() {

        if ( imageField.value.trim() ) {
            const currentImage = {}
            currentImage.size = 1234
            currentImage.name = imageField.value

            this.options.addedfile.call( this, currentImage )
            this.options.thumbnail.call( this, currentImage, `/uploads/${currentImage.name}` )

            currentImage.previewElement.classList.add('dz-success', 'dz-complete')
        }

    },
})

dropzone.on('success', (file, response) => {

    imageField.value = response.image

})

dropzone.on('removedfile', () => {

    imageField.value = ''

})
