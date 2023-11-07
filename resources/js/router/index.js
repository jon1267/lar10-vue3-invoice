import { createRouter, createWebHistory } from 'vue-router';

import invoiceIndex from '../components/invoices/index.vue';
import notFound from '../components/NotFound.vue';
import newInvoice from '../components/invoices/NewInvoice.vue';
import showInvoice from '../components/invoices/ShowInvoice.vue';
import editInvoice from '../components/invoices/EditInvoice.vue';

const routes = [
    {
        path: '/',
        component: invoiceIndex
    },
    {
        path: '/invoice/new',
        component: newInvoice
    },
    {
        path: '/invoice/show/:id',
        component: showInvoice,
        props: true
    },
    {
        path: '/invoice/edit/:id',
        component: editInvoice,
        props: true
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
