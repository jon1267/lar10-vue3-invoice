<script setup>
    import { onMounted, ref } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';

    const router = useRouter();

    let form = ref({ id: '' }); //invoice with id, from db
    let allCustomers = ref([]);
    let customer_id  = ref([]);

    const showModal = ref(false);
    const hideModal = ref(true);
    let listProducts = ref([]);

    const props = defineProps({
        id: {
            type: String,
            default: '',
        }
    });

    const getInvoice = async () => {
        let response = await axios.get(`/api/edit-invoice/${props.id}`);
        console.log(response.data.invoice);
        form.value = response.data.invoice;
    };

    const getAllCustomers = async () => {
        let response = await axios.get('/api/customers')
        //console.log(response.data);
        allCustomers.value = response.data.customers;
    };

    const deleteInvoiceItem = (id,i) => {
        form.value.invoice_items.splice(i,1);
        if (id !== undefined) {
            axios.get(`/api/delete-invoice-item/${id}`);
        }
    };

    const openModal = () => {
        showModal.value = !showModal.value;
    };

    const closeModal = () => {
        showModal.value = !hideModal.value;
    };

    const getProducts = async () => {
        let response = await axios.get('/api/products');
        //console.log('products', response.data.products);
        listProducts.value = response.data.products;
    };

    const addToCart = (item) => {
        const itemcart = {
            product_id: item.id,
            item_code: item.item_code,
            description: item.description,
            unit_price: item.unit_price,
            quantity: item.quantity,
        }

        form.value.invoice_items.push(itemcart); //listCart.value.push(itemcart);
        closeModal();
    };

    const subTotal = () => {
        let subTotal = 0
        if (form.value.invoice_items) {
            form.value.invoice_items.map((item) => {
                subTotal += item.unit_price * item.quantity;
            });
        }
        return subTotal;
    };

    const grandTotal = () => {
        if (form.value.invoice_items) {
            return subTotal() - form.value.discount;
        }
    };

    const onEdit = (id) => {

        if (form.value.invoice_items.length > 0) {
            //console.log(id, form.value.id ,JSON.stringify(form.value.invoice_items));
            let subtotal = 0;
            subtotal = subTotal();

            let total = 0;
            total = grandTotal()

            const formData = new FormData();
            formData.append('invoice_items', JSON.stringify(form.value.invoice_items));
            formData.append('customer_id', form.value.customer_id);
            formData.append('date', form.value.date);
            formData.append('due_date', form.value.due_date);
            formData.append('number', form.value.number);
            formData.append('reference', form.value.reference);
            formData.append('discount', form.value.discount);
            formData.append('subtotal', subtotal);
            formData.append('total', total);
            formData.append('terms_and_conditions', form.value.terms_and_conditions);
            //console.log(formData);

            //form.value.id === id; => `/api/update-invoice/${form.value.id}`
            axios.post(`/api/update-invoice/${id}`, formData);
            form.value.invoice_items = [];
            router.push('/');
        }
    };

    onMounted(async () => {
        await getInvoice();
        await getAllCustomers();
        await getProducts()
    });
</script>

<template>
    <div class="container">

        <div class="invoices">

            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Edit Invoice</h2>
                </div>
                <div>

                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="form.customer_id">
                            <option disabled>Select customer</option>
                            <option :value="customer.id" v-for="customer in allCustomers" :key="customer.id" >
                                {{ customer.firstname || '' }} {{ customer.lastname || '' }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p>
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                        <p class="my-1">Due Date</p>
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Numero</p>
                        <input type="text" class="input" v-model="form.number">
                        <p class="my-1">Reference(Optional)</p>
                        <input type="text" class="input" v-model="form.reference">
                    </div>
                </div>
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>

                    <!-- item 1 -->
                    <div class="table--items2" v-for="(item, i) in form.invoice_items" :key="item.id">
                        <p v-if="item.product">
                            #{{item.product.item_code}} {{ item.product.description }}
                        </p>
                        <p v-else>#{{item.item_code}} {{item.description}}</p>
                        <p>
                            <input type="text" class="input" v-model="item.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model="item.quantity">
                        </p>
                        <p>
                            $ {{ (item.unit_price * item.quantity) || '' }}
                        </p>
                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="deleteInvoiceItem(item.id, i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="openModal()">Add New Line</button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer" >
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ {{ subTotal()  }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="form.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{ grandTotal() || '' }}</span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>

                </div>
                <div>
                    <a class="btn btn-secondary" @click="onEdit(form.id)">
                        Save
                    </a>
                </div>
            </div>

        </div>
        <!--==================== add modal items ====================-->
        <div class="modal main__modal" :class="{show: showModal}">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
                <h3 class="modal__title">Add Item</h3>
                <hr><br>
                <div class="modal__items">
                    <ul class="list-style-none">
                        <li v-for="(item, i) in listProducts" :key="item.id" class="li-grid">
                            <p>{{i+1}}</p>
                            <a href="">{{item.item_code}} {{item.description}}</a>
                            <button @click="addToCart(item)" class="add-button">+</button>
                        </li>
                    </ul>
                </div>
                <br><hr>
                <div class="model__footer">
                    <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.list-style-none {
    list-style: none;
}
.li-grid {
    display: grid;
    grid-template-columns: 30px 350px 15px;
    align-items: center;
    padding-bottom: 5px;
}
.add-button {
    border: 1px solid #e0e0e0;
    width: 35px;
    height: 35px;
    cursor: pointer;
}
</style>
