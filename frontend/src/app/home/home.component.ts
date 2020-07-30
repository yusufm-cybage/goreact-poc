import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { UserService } from '../services/user.service';
import { UtilityService } from '../services/utility.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  uploadForm: FormGroup; 
  fileList: any = [{name: 'abc.jpg'}, {name: 'pqr.jpg'}];
  constructor(private formBuilder: FormBuilder, 
    private userService: UserService,
    private utility: UtilityService) {
    this.uploadForm = this.formBuilder.group({
      selectedFile: ['']
    })
   }

  ngOnInit(): void {
  }

  onFileSelect(event) {
    console.log('called',event.target.files[0] )
    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.uploadForm.get('selectedFile').setValue(file);
    }
  }

  onSubmit() {
    console.log(this.uploadForm.get('selectedFile').value)
    const formData = new FormData();
    formData.append('file', this.uploadForm.get('selectedFile').value);

    // this.userService.uploadFile(formData).subscribe(
    //   res => this.fileUploadSuccess(res),
    //   error => this.utility.displayError(error)
    // );
  }

  fileUploadSuccess(data) {
    console.log('File Uploaded Successfully')
  }


}
