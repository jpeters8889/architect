import Home from './../views/Home'
import BlueprintList from './../views/Blueprints/List'
import BlueprintForm from './../views/Blueprints/Form'

export default [
    {
        path: '/',
        name: 'home',
        component: Home
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
];