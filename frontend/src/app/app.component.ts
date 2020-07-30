import { Component, AfterViewInit } from '@angular/core';
import { UtilityService } from './services/utility.service';
import { UserService } from './services/user.service';
import { error } from 'protractor';
import { Router } from '@angular/router';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements AfterViewInit{
  title = 'goreact';
  isLoggedIn: boolean = false;
  constructor(private utility: UtilityService, private userService: UserService, private router: Router) {
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
    this.logOutSuccess('res');
    // this.userService.logOutUser().subscribe(
    //   res => this.logOutSuccess(res),
    //   error => this.utility.displayError(error)
    // );
  }

  logOutSuccess(data) {
    this.utility.loggedIn.emit(false);
    this.isLoggedIn = false;
    this.router.navigate(['sign-in']);
  }
}