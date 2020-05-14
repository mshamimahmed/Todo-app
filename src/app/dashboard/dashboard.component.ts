import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';  
import { AuthenticationService } from '.././_services/authentication.service';
import { Router } from '@angular/router';



@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  
  todolists = []
  title: string = "";
  description: string = "";
  warningMessage:string = "";
  constructor(public authService: AuthenticationService, public router: Router) { 
    
  }

  ngOnInit(): void {
    this.getTodoList();
  }

  getTodoList(){

    this.authService.getTodoList()
        .subscribe(res => {
          this.todolists = res.data;
          this.todolists.reverse();
          console.log(this.todolists);
          }, error => {
          this.warningMessage = "Someting went wrong "+error.warningMessage;
          console.error(error);
    });
  }

  addTodo(){

    if(!this.title || !this.description){
      this.warningMessage = 'Please Enter Title and Description';
    }else{
      
      this.authService.addTodo(this.title, this.description)
        .subscribe(res => {
          
          this.title = "";
          this.description = "";
          this.warningMessage = 'Successfully Added';
          this.getTodoList();

          }, error => {
          this.warningMessage = "Someting went wrong "+error.warningMessage;
          console.error(error);
        });
    }

   
  }

  showNoteDetails(id){
   this.router.navigate(['/noteDetails/'+id]);  
  }

}
