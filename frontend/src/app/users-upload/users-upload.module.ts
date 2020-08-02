import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UsersUploadRoutingModule } from './users-upload-routing.module';
import { UsersUploadComponent } from './users-upload.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

@NgModule({
  declarations: [UsersUploadComponent],
  imports: [
    CommonModule,
    UsersUploadRoutingModule,
    ReactiveFormsModule,
    FormsModule
  ]
})
export class UsersUploadModule { }
