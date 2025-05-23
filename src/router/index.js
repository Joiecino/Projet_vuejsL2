import { createRouter, createWebHistory } from 'vue-router'
import PageHome from '@/views/PageHome.vue'
import ListageDelete from '@/views/ListageDelete.vue'
import GrapheBilan from '@/views/GrapheBilan.vue'
import BilanGraphe from '@/views/BilanGraphe.vue'
import ModiFication from '@/views/ModiFication.vue'


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: PageHome
        },
        {
            path: '/liste',
            component: ListageDelete
        },
        {
            path: '/graphe',
            component: GrapheBilan
        },
        {
            path: '/graphe2',
            component: BilanGraphe
        },
        {
            path: '/modifier/:id',
            component: ModiFication,
            props: true,
        },
    ]
})
export default router;