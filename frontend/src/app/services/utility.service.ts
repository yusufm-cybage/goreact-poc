import { EventEmitter, Injectable, Output} from '@angular/core';
import { Router } from '@angular/router';
import { NotificationService } from './notification.service';

@Injectable({
  providedIn: 'root'
})
export class UtilityService {
  @Output() loggedIn: EventEmitter<any> = new EventEmitter(true);
  constructor(private router: Router, private notifyService: NotificationService) { }

  displayError(error) {
    if ((error && error.error.message) || error.error.error) {
      this.notifyService.showError(error.error.message ? error.error.message : error.error.error);
    }
    // this.spinnerService.showSpinner.emit(false);
    if (error && error.status && error.status === 401) {
      localStorage.clear();
      this.router.navigate(['/sign-in']);
    }
  }
}
