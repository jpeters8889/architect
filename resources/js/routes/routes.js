import Home from './../views/Home'
import BlueprintList from './../views/Blueprints/List'
import BlueprintForm from './../views/Blueprints/Form'
import Login from "../views/Login";
import Logout from "../views/Logout";
import Error from "../views/Error";
import CustomPage from "../views/CustomPage";
import ChangePassword from "../Components/ChangePassword";

export default [
    {
        path: '/',
        name: 'home',
        component: Home,
    },

    {
        path: '/login',
        name: 'login',
        component: Login,
    },

    {
        path: '/logout',
        name: 'logout',
        component: Logout,
    },

    {
        path: '/error',
        name: 'error',
        component: Error,
    },

    {
        path: '/blueprints/:blueprint',
        name: 'blueprintList',
        component: BlueprintList,
        props: true,
    },

    {
        path: '/blueprints/:blueprint/add',
        name: 'blueprintForm',
        component: BlueprintForm,
        props: true,
    },

    {
        path: '/blueprints/:blueprint/:id',
        name: 'blueprintEdit',
        component: BlueprintForm,
        props: true,
    },

    {
        path: '/page/:page/:id?',
        name: 'customPage',
        component: CustomPage,
        props: true,
    }
];
