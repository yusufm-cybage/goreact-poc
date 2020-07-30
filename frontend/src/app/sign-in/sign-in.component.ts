import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { AuthService } from '../services/auth.service';
import { UtilityService } from '../services/utility.service';
import { Router } from '@angular/router';
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
              private router: Router) { 

    this.signInForm = this.formBuilder.group({
      username: ['', [Validators.required]],
      password: ['', [Validators.required]]
    });
  }
  
  ngOnInit(): void {
  }

  onSubmit(value) {
    console.log(value)
    this.loginSuccess(value);
    if(value) {
      // this.authService.login(value).subscribe(
      //   res => this.loginSuccess(res),
      //   error => this.utility.displayError(error)
      // );
    }
  }

  loginSuccess(data) {
    console.log('Login Successfull');
    this.router.navigate(['home']);
  }



}
