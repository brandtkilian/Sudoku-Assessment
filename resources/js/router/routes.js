import Login from '@/js/pages/login';
import Register from '@/js/pages/register';
import Home from '@/js/pages/home';
import CreateSudoku from '@/js/pages/createsudoku';
import Sudokus from '@/js/pages/sudokus';
import Play from '@/js/pages/play';
import Ranking from '@/js/pages/ranking';


export default [
    { path: '/', name: 'index', component: Home },
    { path: '/home', name: 'home', component: Home },
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
    { path: '/create', name: 'create', component: CreateSudoku },
    { path: '/sudokus', name: 'sudokus', component: Sudokus },
    { path: '/sudokus/:id', name: 'playsudokus', component: Play },
    { path: '/ranking', name: 'ranking', component: Ranking },
]
