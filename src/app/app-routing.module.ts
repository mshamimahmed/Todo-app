import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from '../app/login/login.component'
import { from } from 'rxjs';
import { DashboardComponent } from './dashboard/dashboard.component';
import { NoteDeatailsComponent } from './note-deatails/note-deatails.component';
import { ProfileComponent } from './profile/profile.component';
import { loginRoutes }    from './login/login.routes';  
import { DashboardRoutes }    from './dashboard/dashboard.routes';  

const routes: Routes = [
  
  { path: '', component: LoginComponent  },
  { path: 'dashbaord', component: DashboardComponent  },
  { path: 'noteDetails/:id', component: NoteDeatailsComponent  },
  { path: 'profile', component: ProfileComponent  }

  // ...loginRoutes,
  // ...DashboardRoutes

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
