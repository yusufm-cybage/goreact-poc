import { Component, OnInit } from '@angular/core';
import { UserService } from '../services/user.service';
import { UtilityService } from '../services/utility.service';
import { environment } from '../../environments/environment';
@Component({
  selector: 'app-users-upload',
  templateUrl: './users-upload.component.html',
  styleUrls: ['./users-upload.component.scss']
})
export class UsersUploadComponent implements OnInit {
  fileList: any = [];
  baseUrl: any = '';
  searchQuery: any = '';
  isSearch: boolean = false;
  constructor(private userService: UserService, private utility: UtilityService) { 
    this.baseUrl = environment.fileBaseURL;
    this.getAllMediaPosts();
  }

  ngOnInit(): void {
  }

  /**
   * To get all mediaposts uploaded by all users
   */
  private getAllMediaPosts() {
    this.utility.showSpinner.emit(true);
    this.userService.getAllMediaPosts().subscribe(
      res => this.getAllMediaPostsSuccess(res),
      error => this.utility.displayError(error)
    );
  }

  /**
   * Called when ageAllMediaPosts API success
   * @param data 
   */
  private getAllMediaPostsSuccess(data) {
    this.fileList = [];
    if(data && data.data && data.data.length) {
      data.data.forEach(item => {
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
   * To show file on table
   * @param index 
   */
  openFile(index) {
    this.fileList[index].isOpen = !this.fileList[index].isOpen;
  }

  trackByFn(index) {
    return index;
  }

  /**
   * To search the file uploaded by user
   */
  searchFile() {
    if(this.searchQuery) {
      this.isSearch = true;
      this.utility.showSpinner.emit(true);
      let searchPayload = {
        query: this.searchQuery
      }
      this.userService.searchFile(searchPayload).subscribe(
        res => this.getAllMediaPostsSuccess(res),
        error => this.utility.displayError(error)
      )
    }
  }

  /**
   * To reset the search
   */
  resetSearch() {
    if (this.searchQuery && this.isSearch) {
      this.searchQuery = '';
      this.getAllMediaPosts();
    } else {
      this.searchQuery = '';
    }
  }
}
