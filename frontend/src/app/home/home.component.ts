import { Component, OnInit, AfterViewInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { UserService } from '../services/user.service';
import { UtilityService } from '../services/utility.service';
import { BlankSpaceValidator } from '../shared/validators/blank.validator';
import { NotificationService } from '../services/notification.service';
import { environment } from '../../environments/environment'
@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {
  uploadForm: FormGroup; 
  fileList: any = [];
  fileValue: any;
  baseUrl: any = '';
  searchQuery: any = '';
  isSearch: boolean = false;
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
    this.baseUrl = environment.fileBaseURL;
    this.getUsersFileList();
   }

  ngOnInit(): void {
  }

  /**
   * To get list of file uploaded by users
   */
  private getUsersFileList() {
    this.utility.showSpinner.emit(true);
    this.userService.getUserUploadedFiles(localStorage.getItem('uuid')).subscribe(
      res => this.getUsersFileListSuccess(res),
      error => this.utility.displayError(error)
    );
  }

  /**
   * On success of get list of file uploaded by users
   * @param res 
   */
  private getUsersFileListSuccess(res) {
    this.fileList = [];
    if(res && res.data && res.data.length) {
      res.data.forEach(item => {
        item.isOpen = false;
        item.filePath = this.baseUrl + item.file_name;
        this.fileList.push(item);
      });
      this.utility.showSpinner.emit(false);
    } else {
      this.utility.showSpinner.emit(false);
    }
  }

  /**
   * When user clicks on file input
   * @param event 
   */
  onFileSelect(event) {
    if (event.target.files.length > 0) {
      this.fileValue = event.target.files[0];
    }
  }

  /**
   * When user click upload button, and to call upload file API
   * @param value 
   */
  onSubmit(value) {
    const formData: FormData = new FormData();
    formData.append('file',this.fileValue ); 
    formData.append('title', value.fileTitle);
    formData.append('tag', value.fileTag);
    formData.append('description', value.fileDescription);
    this.utility.showSpinner.emit(true);
    this.userService.uploadFile(formData).subscribe(
      res => this.fileUploadSuccess(res),
      error => this.utility.displayError(error)
    );
  }

  /**
   * Called when file uploaded successfully
   * @param data 
   */
  private fileUploadSuccess(data) {
    if(data && data.code == 200) {
      this.notyfy.showSuccess('File Uploaded Successfully');
      this.uploadForm.reset();
      this.fileValue = '';
      this.getUsersFileList();
    } else {
      this.utility.showSpinner.emit(false)
    }
  }

  /**
   * To show file on click of file name
   * @param index 
   */
  openFile(index) {
    this.fileList[index].isOpen = !this.fileList[index].isOpen;
  }

  trackByFn(index) {
    return index;
  }

  /**
   * Called on search button click
   */
  searchFile() {
    if(this.searchQuery) {
      this.isSearch = true;
      this.utility.showSpinner.emit(true);
      let searchPayload = {
        query: this.searchQuery
      }
      this.userService.searchFile(searchPayload).subscribe(
        res => this.getUsersFileListSuccess(res),
        error => this.utility.displayError(error)
      )
    }
  }

  /**
   * To reset the search result
   */
  resetSearch() {
    if (this.searchQuery && this.isSearch) {
      this.searchQuery = '';
      this.getUsersFileList();
    } else {
      this.searchQuery = '';
    }
  }
}
