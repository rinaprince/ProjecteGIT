import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['container', 'collectionContainer']

    static values = {
        index    : Number,
        prototype: String,
    }

    connect() {
        super.connect();
        this.addImageDeleteButton();
    }

    addImageElement(event)
    {
        const item = document.createElement('div');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        //console.log(this.indexValue);
        this.containerTarget.append(item);
        this.indexValue++;
    }

    addImageDeleteButton () {
        this.collectionContainerTargets.forEach((div) => {
                this.addImageFormDeleteLink(div)
            })

        const addFormToCollection = (e) => {
            this.addImageFormDeleteLink(e);
        }
    }

    addImageFormDeleteLink = (item) => {
        const removeFormButton = document.createElement('button');
        removeFormButton.classList.add('btn');
        removeFormButton.classList.add('btn-danger');
        removeFormButton.innerText = 'Delete this image';

        item.append(removeFormButton);

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            // remove the li for the tag form
            item.remove();
        });
    }
}
