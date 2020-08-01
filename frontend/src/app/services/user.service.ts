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
      'X-Requested-With':'XMLHttpRequest',
      Authorization :'Bearer ' + localStorage.getItem('token')
    }));
  }

  getUserDetails(): Observable<any> {
    return this.http.get(this.baseURL + 'user', {
      headers: this.createHeader()
    });
  }

  uploadFile(data):Observable<any> {
   let fileheader = new HttpHeaders({
      "Accept": "application/json",
      'X-Requested-With':'XMLHttpRequest',
      Authorization :'Bearer ' + localStorage.getItem('token')
    });
    console.log(data)
    return this.http.post(this.baseURL + 'mediapost', data, { headers: fileheader})
  }

  getUserUploadedFiles(id): Observable<any> {
    return this.http.get(this.baseURL + 'showmediapost/' + id, {
      headers: this.createHeader()
    });
  }

  getAllMediaPosts(): Observable<any> {
    return this.http.get(this.baseURL + 'mediapost', { headers: this.createHeader()})
  }

  logOutUser(): Observable<any> {
    return this.http.get(this.baseURL + 'logout', { headers: this.createHeader()})
  }
}
