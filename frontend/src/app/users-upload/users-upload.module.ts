import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UsersUploadRoutingModule } from './users-upload-routing.module';
import { UsersUploadComponent } from './users-upload.component';


@NgModule({
  declarations: [UsersUploadComponent],
  imports: [
    CommonModule,
    UsersUploadRoutingModule
  ]
})
export class UsersUploadModule { }
