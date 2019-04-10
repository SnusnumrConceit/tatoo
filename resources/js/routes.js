import Users from './components/admin/users/users';
import UserForm from './components/admin/users/user_form';

import Appointments from './components/admin/appointments/appointments';
import AppointmentForm from './components/admin/appointments/appointment_form';

import Tatoos from './components/admin/tatoos/tatoos';
import TatooForm from './components/admin/tatoos/tatoo_form';

import Employees from './components/admin/employees/employees';
import EmployeeForm from './components/admin/employees/employee_form';

import Orders from './components/admin/orders/orders';
import OrderForm from './components/admin/orders/order_form';

export const routes = [
  {
    name: 'users',
    path: '/users',
    component: Users
  },
  {
    name: 'user_form',
    path: '/users/create',
    component: UserForm
  },
  {
    name: 'user_form',
    path: '/users/:id',
    component: UserForm
  },
  {
    name: 'tatoos',
    path: '/tatoos',
    component: Tatoos
  },
  {
    name: 'tatoo_form',
    path: '/tatoos/create',
    component: TatooForm
  },
  {
    name: 'tatoo_form',
    path: '/tatoos/:id',
    component: TatooForm
  },
  {
    name: 'employees',
    path: '/employees',
    component: Employees
  },
  {
    name: 'employee_form',
    path: '/employees/create',
    component: EmployeeForm
  },
  {
    name: 'employee_form',
    path: '/employees/:id',
    component: EmployeeForm
  },
  {
    name: 'orders',
    path: '/orders',
    component: Orders
  },
  {
    name: 'order_form',
    path: '/orders/create',
    component: OrderForm
  },
  {
    name: 'order_form',
    path: '/orders/:id',
    component: OrderForm
  },
  {
    name: 'appointments',
    path: '/appointments',
    component: Appointments
  },
  {
    name: 'appointment_form',
    path: '/appointments/create',
    component: AppointmentForm
  },
  {
    name: 'appointment_form',
    path: '/appointments/:id',
    component: AppointmentForm
  }
];