import { Component, AfterViewInit } from '@angular/core';
import { UtilityService } from './services/utility.service';
import { UserService } from './services/user.service';
import { error } from 'protractor';
import { Router } from '@angular/router';
import { NotificationService } from './services/notification.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'goreact';
  isLoggedIn: boolean = false;
  isAdmin: boolean = false;
  userName: any = '';
  constructor(private utility: UtilityService, private userService: UserService, 
    private router: Router, private notifyService : NotificationService) {
    if(localStorage.getItem('token')) {
      this.isLoggedIn = true;
      this.utility.loggedIn.emit(true);
      this.userName = localStorage.getItem('name') ? localStorage.getItem('name') : ''; 
      if( localStorage.getItem('isAdmin')) {
        this.isAdmin = true;
      } else {
        this.isAdmin = false;
      }
    } else {
      this.isLoggedIn = false;
      this.utility.loggedIn.emit(false);
    }
  }

  ngAfterViewInit(): void {
    // to catch loggedIn event
    this.utility.loggedIn.subscribe(item => {
        this.isLoggedIn = item;
        this.userName = localStorage.getItem('name') ? localStorage.getItem('name') : ''; 
        if( localStorage.getItem('isAdmin')) {
          this.isAdmin = true;
        } else {
          this.isAdmin = false;
        }
    })
  }

  /**
   * Called when user clicks on logout button
   */
  logOut() {
    this.utility.showSpinner.emit(true);
    this.userService.logOutUser().subscribe(
      res => this.logOutSuccess(res),
      error => this.utility.displayError(error)
    );
  }

  /**
   * Called when logout success
   * @param data 
   */
  private logOutSuccess(data) {
    this.notifyService.showSuccess("Logout Successfull");
    this.utility.loggedIn.emit(false);
    this.isLoggedIn = false;
    this.isAdmin = false;
    this.userName = '';
    this.utility.showSpinner.emit(false)
    this.router.navigate(['sign-in']);
  }
}