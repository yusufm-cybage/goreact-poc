import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { AuthService } from '../services/auth.service';
import { UtilityService } from '../services/utility.service';
import { Router } from '@angular/router';
import { EmailValidator } from 'src/app/shared/validators/email.validators'
import { BlankSpaceValidator } from '../shared/validators/blank.validator';
import { NotificationService } from '../services/notification.service';
@Component({
  selector: 'app-sign-in',
  templateUrl: './sign-in.component.html',
  styleUrls: ['./sign-in.component.scss']
})
export class SignInComponent implements OnInit {
  signInForm: FormGroup;
  constructor(private formBuilder: FormBuilder,
              private authService: AuthService,
              private utility: UtilityService,
              private router: Router,
              private notifyService : NotificationService) { 

    this.signInForm = this.formBuilder.group({
      password: ['', [Validators.required, BlankSpaceValidator.validate]],
      username: ['', [Validators.required, EmailValidator.validate]],
    });
    localStorage.removeItem('token');
    this.utility.loggedIn.emit(false);
  }
  
  ngOnInit(): void {
  }

  onSubmit(value) {
    if(value) {
      let loginData = {
        email: value.username,
        password: value.password
      }
      this.authService.login(loginData).subscribe(
        res => this.loginSuccess(res),
        error => this.utility.displayError(error)
      );
    }
  }

  loginSuccess(data) {
    if (data.access_token && data.uuid) {
      localStorage.setItem('token', data.access_token);
      localStorage.setItem('uuid', data.uuid);
      localStorage.setItem('name', data.name);
      this.router.navigate(['home']);
      this.utility.loggedIn.emit(true);
      this.notifyService.showSuccess("Login Successfull");
    }
  }
}
