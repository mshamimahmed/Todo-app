import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ActivatedRoute } from '@angular/router';  
import { AuthenticationService } from '.././_services/authentication.service';

@Component({
  selector: 'app-note-deatails',
  templateUrl: './note-deatails.component.html',
  styleUrls: ['./note-deatails.component.css']
})
export class NoteDeatailsComponent implements OnInit {

  title : string = "";
  description : string = "";
  warningMessage : string = "";
  noteId : string = "";

  constructor(
    public authService: AuthenticationService,
    public router: Router,
  	public route: ActivatedRoute) {
      this.route.params.subscribe(params => {
        this.noteId = params['id'];
      });
    }

    ngOnInit(): void {
        if(this.noteId){ 

          this.authService.getTodobyId(this.noteId)
          .subscribe(res => {
                //console.log(res);
                this.title = res.data[0].title;
                this.description = res.data[0].desc;
                }, error => {
                this.warningMessage = "Someting went wrong "+error.warningMessage;
                console.error(error);
          });
        }
    }

    updateNote(){

      if(!this.title || !this.description){
        this.warningMessage = 'Please Enter Title and Description';
      }else{
        
        this.authService.updateNote(this.title,this.description, this.noteId)
          .subscribe(res => {

            if(res.success==true){
                this.warningMessage = 'Successfully Updated';
            }else{
                this.warningMessage = 'Something went wrong while updating';
            }
  
            }, error => {
            this.warningMessage = "Someting went wrong "+error.warningMessage;
            console.error(error);
          });
      }
    }

    deleteNote(){
       
      this.authService.deleteNote(this.noteId)
          .subscribe(res => {
            
            this.warningMessage = 'Successfully Deleted';
            this.router.navigate(['/dashbaord']); 
        
            }, error => {
            this.warningMessage = "Someting went wrong "+error.warningMessage;
            console.error(error);
          });
    }
   

}
