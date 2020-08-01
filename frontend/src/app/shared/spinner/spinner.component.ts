import { Component, OnInit, AfterViewInit } from '@angular/core';
import { UtilityService } from 'src/app/services/utility.service';

@Component({
  selector: 'app-spinner',
  templateUrl: './spinner.component.html',
  styleUrls: ['./spinner.component.scss']
})
export class SpinnerComponent implements OnInit, AfterViewInit {
  show: Boolean = false;
  constructor(private utility: UtilityService) { 
    this.utility.showSpinner.subscribe(item => {
      this.show = item ? true : false;
    });
  }

  ngOnInit(): void {
  }

  ngAfterViewInit(): void {
    this.utility.showSpinner.subscribe(item => {
      this.show = item ? true : false;
    });
  }

}
