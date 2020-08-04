import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { AuthService } from '../services/auth.service';
import { UtilityService } from '../services/utility.service';
import { Router } from '@angular/router';
import { EmailValidator } from 'src/app/shared/validators/email.validators'
import { BlankSpaceValidator } from '../shared/validators/blank.validator';
import { NotificationService } from '../services/notification.service';
export interface LoginPara {
  username: string;
  password: string;
}

export interface LoginSuccess {
  access_token: string;
  expires_at: string;
  isAdmin: number;
  message: string;
  name: string;
  token_type: string;
  uuid: string;
}

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
    localStorage.clear();
    this.utility.loggedIn.emit(false);
  }
  
  ngOnInit(): void {
  }

  /**
   * To call sign in API
   * @param value 
   */
  onSubmit(value: LoginPara) {
    if(value) {
      let loginData = {
        email: value.username,
        password: value.password
      }
      this.utility.showSpinner.emit(true);
      this.authService.login(loginData).subscribe(
        res => this.loginSuccess(res),
        error => this.utility.displayError(error)
      );
    }
  }

  /**
   * On successfull login API
   * @param data 
   */
  private loginSuccess(data: LoginSuccess) {
    if (data.access_token && data.uuid) {
      localStorage.setItem('token', data.access_token);
      localStorage.setItem('uuid', data.uuid);
      localStorage.setItem('name', data.name);
      if(data.isAdmin == 1) {
        localStorage.setItem('isAdmin', '1');
      }
      this.router.navigate(['home']);
      this.utility.loggedIn.emit(true);
      this.notifyService.showSuccess("Login Successfull");
    } else {
      this.utility.showSpinner.emit(false);
    }
  }
}
