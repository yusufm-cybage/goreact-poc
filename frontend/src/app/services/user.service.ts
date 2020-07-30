import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  baseURL: string = environment.baseURL;
  header: any;

  constructor(private http: HttpClient) { }

  createHeader() {
    return (this.header = new HttpHeaders({
      'Content-Type': 'application/json',
      Authorization: 'bearer ' + localStorage.getItem('token')
    }));
  }

  getUserDetails(): Observable<any> {
    return this.http.get(this.baseURL + 'users/details', {
      headers: this.createHeader()
    });
  }

  uploadFile(data):Observable<any> {
    return this.http.post(this.baseURL + '', data, { headers: this.createHeader()})
  }
}
