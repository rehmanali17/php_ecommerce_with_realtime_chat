// Add Product Modal Elements
const addProductModal = document.getElementById('addProductModal')
const addProductImage = document.getElementById('addProductImage');
const addProductImagePreview = document.getElementById('addProductImagePreview');
const addProductImagePreviewContainer = document.getElementById('addProductImagePreviewContainer');
const addProductName = document.getElementById('addProductName');
const addProductDescription = document.getElementById('addProductDescription');
const addProductPrice = document.getElementById('addProductPrice');
const addProductQuantity = document.getElementById('addProductQuantity');

// Update Product Modal Elements
const updateProductId = document.getElementById('updateProductId');
const updateProductModal = document.getElementById('updateProductModal')
const updateProductName = document.getElementById('updateProductName');
const updateProductDescription = document.getElementById('updateProductDescription');
const updateProductPrice = document.getElementById('updateProductPrice');
const updateProductQuantity = document.getElementById('updateProductQuantity');
const updateProductModalBtn = document.getElementById('updateProductModalBtn');

// Chat Container Elements
const chatMessageInput = document.getElementById('chatMessageInput');
const chatHistoryContainer = document.getElementById('chatHistoryContainer');
let incomingId = 0;

addProductModal.addEventListener('shown.bs.modal', () => {
    addProductName.focus();
})

function addProductPreviewImage() {
    const file = addProductImage.files[0];
    if(file){
        addProductImagePreviewContainer.classList.remove('visually-hidden')
    }else{
        addProductImagePreviewContainer.classList.add('visually-hidden')
    }

    const reader = new FileReader();
    reader.onloadend = function () {
        addProductImagePreview.src = reader.result;
    };

    if (file && file.type.startsWith('image/')) {
        reader.readAsDataURL(file);
    } else {
        addProductImagePreview.src = '';
    }
}

function saveProduct(){

    if(addProductName.value && addProductDescription.value && addProductPrice.value && addProductQuantity.value && addProductImage.files[0]){

        const productData = {
            name: addProductName.value,
            description: addProductDescription.value,
            quantity: addProductQuantity.value,
            price: addProductPrice.value,
        }

        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log('Response:', xhr.responseText, xhr.status, xhr.statusText);
                location.reload();
            }
        };

        const formData = new FormData();
        formData.append('file', addProductImage.files[0]);
        formData.append('productData', JSON.stringify(productData));

        xhr.open('POST', '../../api/product/save-product.php', true);
        xhr.send(formData);
    }

}

document.addEventListener("click", async function(event){
    if(event.target.id === 'deleteProductBtn'){
        const id = event.target.getAttribute('data-id');
        const apiUrl = '../../api/product/delete-product.php';
        await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id })
        });
        location.reload()
    }

    if(event.target.id === 'chatHistoryUser'){
        incomingId = event.target.getAttribute('data-id');
        removeClass('chat-history-active-user');
        event.target.parentNode.parentNode.classList.add('chat-history-active-user');;
    }

    if(event.target.id === 'fill-tab-0' || event.target.id === 'fill-tab-1'){
        incomingId = 0;
    }

    if(event.target.id === 'sendChatMessageBtn'){
        const message = chatMessageInput.value;
        if(message){
            const messageData = {
                incoming_id: incomingId,
                message
            }
            let xhr = new XMLHttpRequest();
            xhr.onload = ()=>{
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        chatMessageInput.value = "";
                        scrollToBottom();
                    }
                }
            }
            let formData = new FormData();
            formData.append('chatData', JSON.stringify(messageData))

            xhr.open("POST", "../../api/chat/insert-chat.php", true);
            xhr.send(formData);
        }
    }

});

async function openUpdateProductModal(id) {
    const product = await fetchSingleProduct(id)
    updateProductId.value = product['id'];
    updateProductName.value = product['name'];
    updateProductDescription.value = product['description'];
    updateProductPrice.value = product['price'];
    updateProductQuantity.value = product['quantity'];
    updateProductModalBtn.click();
}

async function fetchSingleProduct(id) {
    const apiUrl = '../../api/product/fetch-single-product.php';

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id })
        });

        const product = await response.json();

        return product;
    } catch (error) {
        console.error('Fetch error:', error);
        return {};
    }
}

updateProductModal.addEventListener('shown.bs.modal', () => {
    updateProductName.focus();
})

function updateProduct(){

    if(updateProductName.value && updateProductDescription.value && updateProductPrice.value && updateProductQuantity.value){

        const productData = {
            id: updateProductId.value,
            name: updateProductName.value,
            description: updateProductDescription.value,
            quantity: updateProductQuantity.value,
            price: updateProductPrice.value,
        }

        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log('Response:', xhr.responseText, xhr.status, xhr.statusText);
                location.reload();
            }
        };

        const formData = new FormData();
        formData.append('productData', JSON.stringify(productData));

        xhr.open('POST', '../../api/product/update-product.php', true);
        xhr.send(formData);
    }
}

setInterval(() =>{
    if(incomingId){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "../../api/chat/get-chat.php", true);
        xhr.onload = ()=>{
            if(xhr.readyState === XMLHttpRequest.DONE){
                if(xhr.status === 200){
                    let data = xhr.response;
                    chatHistoryContainer.innerHTML = data;
                    scrollToBottom();
                }
            }
        }
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("incoming_id=" + incomingId);
    }
}, 500);

function scrollToBottom(){
    chatHistoryContainer.parentNode.scrollTop = chatHistoryContainer.scrollHeight;
}

function removeClass(className) {
    const elements = document.querySelectorAll('.' + className);

    for (let i = 0; i < elements.length; i++) {
        elements[i].classList.remove(className);
    }
}