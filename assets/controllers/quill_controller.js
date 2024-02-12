import { Controller } from '@hotwired/stimulus';
import Quill from "quill";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['editor']
    // ...
    connect() {
        this.quill()
    }

    quill() {
        this.editorTargets.forEach((editor)=> {

            const textarea = editor.parentElement.querySelector('input');

            const quill = new Quill(editor, {
                theme: 'snow',
                modules: {
                    toolbar: [['bold', 'italic', 'underline', 'strike'],[{ 'list': 'ordered'}, { 'list': 'bullet' }]]
                }
            });

            quill.container.style['height'] = '16em';

           // console.log(textarea);

            quill.on('text-change' , function(delta, oldDelta, source) {
                textarea.value = quill.root.innerHTML;
            });

        })
    }
}
