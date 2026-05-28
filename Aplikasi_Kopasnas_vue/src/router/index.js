import { createRouter, createWebHistory } from 'vue-router'

import Landing from '../views/Landing.vue'
import Login from '../views/Login.vue'
import DashboardAdmin from '../views/DashboardAdmin.vue'
import DashboardAnggota from '../views/DashboardAnggota.vue'
import Anggota from '../views/Anggota.vue'
import Voting from '../views/Voting.vue'
import HasilVoting from '../views/HasilVoting.vue'
import DetailVoting from '../views/DetailVoting.vue'


const routes = [

{
    path:'/',
    component: Landing
},

{
    path:'/login',
    component: Login
},

{
    path:'/dashboard-admin',
    component: DashboardAdmin
},

{
    path:'/dashboard-anggota',
    component: DashboardAnggota
},

{
    path:'/anggota',
    component: Anggota
    
},

{
    path:'/voting',
    component: Voting
},

{
    path:'/hasil-voting',
    component: HasilVoting
},

{
  path:'/voting/:id',
  component: DetailVoting
}

]

const router = createRouter({

history:createWebHistory(),
routes

})

router.beforeEach((to,from,next)=>{

const role = localStorage.getItem('role')

const halamanAdmin = [
'/dashboard-admin'
]

const butuhLogin = [
'/dashboard-admin',
'/dashboard-anggota',
'/anggota',
'/voting',
'/hasil-voting'
]

const perluLogin =
butuhLogin.includes(to.path)
||
to.path.startsWith('/voting/')

if(perluLogin && !role){

return next('/login')

}

if(
halamanAdmin.includes(to.path)
&& role !== 'admin'
){

return next('/dashboard-anggota')

}

next()

})
export default router