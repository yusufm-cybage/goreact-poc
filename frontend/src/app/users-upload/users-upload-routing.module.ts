import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { UsersUploadComponent } from './users-upload.component';


const routes: Routes = [
  {
    path: '',
    component: UsersUploadComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UsersUploadRoutingModule { }
