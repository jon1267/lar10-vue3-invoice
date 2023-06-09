import { createRouter, createWebHistory } from 'vue-router';

import invoiceIndex from '../components/invoices/index.vue';
import notFound from '../components/NotFound.vue';
import newInvoice from '../components/invoices/NewInvoice.vue';

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
        path: '/:pathMatch(.*)*',
        component: notFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
