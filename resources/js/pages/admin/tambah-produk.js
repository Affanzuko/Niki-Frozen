// FilePond core
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';

// FilePond plugins
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

// Register plugins
FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageResize,
    FilePondPluginImageTransform,
    FilePondPluginFileValidateType
);

document.addEventListener('DOMContentLoaded', function () {
    const inputElement = document.querySelector('#image');
    if (!inputElement) return;

    FilePond.create(inputElement, {
        // Labels
        labelIdle: 'Seret & lepas gambar atau <span class="filepond--label-action">Pilih File</span>',

        // File type validation
        acceptedFileTypes: ['image/jpeg', 'image/png', 'image/webp'],
        fileValidateTypeLabelExpectedTypesMap: {
            'image/jpeg': '.jpg',
            'image/png': '.png',
            'image/webp': '.webp',
        },

        // Image crop — lock to 4:3 ratio
        allowImageCrop: true,
        imageCropAspectRatio: '4:3',

        // Image resize — target 800x600 (4:3)
        allowImageResize: true,
        imageResizeTargetWidth: 800,
        imageResizeTargetHeight: 600,
        imageResizeMode: 'cover',
        imageResizeUpscale: true,

        // Image transform — output as JPEG quality 80
        allowImageTransform: true,
        imageTransformOutputMimeType: 'image/jpeg',
        imageTransformOutputQuality: 80,

        // Preview panel — 4:3 aspect ratio (height/width = 3/4 = 0.75)
        stylePanelAspectRatio: '0.40',
        stylePanelLayout: 'compact',

        // Single file only
        allowMultiple: false,
        maxFiles: 1,

        // Store file in input element for regular form submission (no server endpoint)
        storeAsFile: true,
    });
});
