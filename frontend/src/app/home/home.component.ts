import { Component, OnInit, AfterViewInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { UserService } from '../services/user.service';
import { UtilityService } from '../services/utility.service';
import { BlankSpaceValidator } from '../shared/validators/blank.validator';
import { NotificationService } from '../services/notification.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  uploadForm: FormGroup; 
  fileList: any = [];
  fileValue: any;
  constructor(private formBuilder: FormBuilder, 
    private userService: UserService,
    private utility: UtilityService, 
    private notyfy: NotificationService) {
    this.uploadForm = this.formBuilder.group({
      selectedFile: [''],
      fileTitle: ['', [Validators.required, BlankSpaceValidator.validate]],
      fileTag: ['', [Validators.required, BlankSpaceValidator.validate]],
      fileDescription: ['', [Validators.required, BlankSpaceValidator.validate]]
    });
    this.getUsersFileList();
   }

  ngOnInit(): void {
  }

  getUsersFileList() {
    this.userService.getUserUploadedFiles(localStorage.getItem('uuid')).subscribe(
      res => this.getUsersFileListSuccess(res),
      error => this.utility.displayError(error)
    );
  }

  getUsersFileListSuccess(res) {
    console.log(res)
    if(res && res.data && res.data.length) {
      this.fileList = res.data
    }
  }

  onFileSelect(event) {
    if (event.target.files.length > 0) {
      this.fileValue = event.target.files[0];
    }
  }

  onSubmit(value) {
    const formData: FormData = new FormData();
    formData.append('file',this.fileValue ); 
    formData.append('title', value.fileTitle);
    formData.append('tag', value.fileTag);
    formData.append('description', value.fileDescription);

    this.userService.uploadFile(formData).subscribe(
      res => this.fileUploadSuccess(res),
      error => this.utility.displayError(error)
    );
  }

  fileUploadSuccess(data) {
    if(data && data.code == 200) {
      this.notyfy.showSuccess('File Uploaded Successfully');
      this.uploadForm.reset();
      this.fileValue = '';
      this.getUsersFileList();
    }
  }


}
