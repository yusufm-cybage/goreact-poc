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
  constructor(private utility: UtilityService, private userService: UserService, 
    private router: Router, private notifyService : NotificationService) {
    if(localStorage.getItem('token')) {
      this.isLoggedIn = true;
      this.utility.loggedIn.emit(true);
    } else {
      this.isLoggedIn = false;
      this.utility.loggedIn.emit(false);
    }
  }

  ngAfterViewInit(): void {
    this.utility.loggedIn.subscribe(item => {
        this.isLoggedIn = item;
    })
  }

  logOut() {
    this.userService.logOutUser().subscribe(
      res => this.logOutSuccess(res),
      error => this.utility.displayError(error)
    );
  }

  logOutSuccess(data) {
    this.notifyService.showSuccess("Logout Successfull");
    this.utility.loggedIn.emit(false);
    this.isLoggedIn = false;
    this.router.navigate(['sign-in']);
  }
}