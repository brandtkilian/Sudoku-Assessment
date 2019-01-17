import Vue from 'vue'
import Child from './Child'
import Card from './Card'
import Checkbox from './Checkbox'
import Home from './Home'
import Navbar from './Navbar'
import FormError from './FormError'
import SudokuGrid from './SudokuGrid'
import { HasError, AlertError, AlertSuccess } from 'vform'

// Components that are registered globaly.
[
  Child,
  Card,
  Checkbox,
  Home,
  FormError,
  Navbar,
  SudokuGrid
].forEach(Component => {
  Vue.component(Component.name, Component)
})
