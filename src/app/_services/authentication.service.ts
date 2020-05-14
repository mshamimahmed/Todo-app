import { Injectable } from '@angular/core';
import { HttpHeaders } from '@angular/common/http';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { environment } from '../../environments/environment';

@Injectable({ providedIn: 'root' }) 
export class AuthenticationService {
  
  public token: string;
  public headers: HttpHeaders;
  public readonly apiUrl = environment.apiUrl;
  public readonly baseUrl = environment.baseUrl;

  constructor(public http: HttpClient) {
  }
  httpOptions = {

    headers: new HttpHeaders({ 
      'Content-Type': 'application/json',
     })
  };
  

  login(eamil: string, password: string): Observable<any> {
    
    var raw = JSON.stringify({ email: eamil, password: password });
    return this.http.post(this.apiUrl+"users/login", JSON.parse(raw))
        .pipe(
            map((response: Response) => {

                if(response['success']==true){
                  this.token = response['data']['token'];
                  console.log(this.token);
                  if (this.token) {
                      sessionStorage.setItem('token', this.token);
                  }
                }
                return response;
            })
        );
}


addTodo(title: string, description: string): Observable<any> {
  
  var header = {
    headers: new HttpHeaders()
     .set('Authorization',  `Bearer ${sessionStorage.getItem("token")}`)
  }
  
  var raw = JSON.stringify({ title: title, desc: description, date: "2019-02-12" });
  return this.http.post(this.apiUrl+"/todo", JSON.parse(raw),header)
      .pipe(
          map((response: Response) => {
             return response;
          })
      );
}

getTodoList(): Observable<any> {
  
  var header = {
    headers: new HttpHeaders()
     .set('Authorization',  `Bearer ${sessionStorage.getItem("token")}`)
  }
  return this.http.get(this.apiUrl+"todo/",header)
      .pipe(
          map((response: Response) => {
             console.log(response);
             return response; 
          })
      );
}

getTodobyId(noteId): Observable<any> {
  
  var header = {
    headers: new HttpHeaders()
     .set('Authorization',  `Bearer ${sessionStorage.getItem("token")}`)
  }
  return this.http.get(this.apiUrl+"todo/"+noteId,header)
      .pipe(
          map((response: Response) => {
             return response;
          })
      );
}

updateNote(title: string,description:string, noteId: string): Observable<any> {
  
  var header = {
    headers: new HttpHeaders()
     .set('Authorization',  `Bearer ${sessionStorage.getItem("token")}`)
  }

  var raw = JSON.stringify({ title: title ,desc: description});
  return this.http.put(this.apiUrl+"/todo/"+noteId, JSON.parse(raw),header)
      .pipe(
          map((response: Response) => {
             return response;
          })
      );
}

deleteNote(noteId): Observable<any> {
  
  var header = {
    headers: new HttpHeaders()
     .set('Authorization',  `Bearer ${sessionStorage.getItem("token")}`)
  }
  
  return this.http.delete(this.apiUrl+"/todo/"+noteId,header)
      .pipe(
          map((response: Response) => {
             return response;
          })
      );
}
  
}
