import { Component, OnInit } from '@angular/core';
import { LandaService } from '../../core/services/landa.service';

@Component({
  selector: 'app-new',
  templateUrl: './new.component.html',
  styleUrls: ['./new.component.scss']
})
export class NewComponent implements OnInit {

  constructor(
    private Landa_: LandaService,
  ) { }

  ngOnInit(): void {
    this.get_item();
    this.get_cabang();
  }

  data_item: any = [];
  data_cabang: any = [];
  data_gudang: any =[];
  data_all:any;
  saldo_awal: any;
  kk: any;

  f_item: any;
  f_cabang: any;
  f_gudang: any;
  selected_item: any;

  tgl_start: any;
  tgl_end: any;
  public daterange: any = {};
  public dt_range_options: any = {
    locale: {format: 'YYYY-MM-DD'},
    alwaysShowCalendars: false
  };



  public selectedDate(value: any, datepicker?: any){
    // console.log(value);
    // new Date().
    // console.log(this.toDate_convert(value.start._d));
    this.tgl_start = this.toDate_convert(value.start._d);
    this.tgl_end   = this.toDate_convert(value.end._d);

    datepicker.start = value.start;
    datepicker.end = value.end;
    
    // this.formatter.format() 
    // console.log(this.tgl_start);
    // console.log(this.tgl_end);

    this.daterange.start = value.start;
    this.daterange.end   = value.end;
    this.daterange.label = value.label;

    // console.log(value.start);
  }

  toDate_convert(dt){
    var year = dt.getFullYear();
    var month= dt.getMonth()+1;
    var date = dt.getDate();
    // var time = dt.getUTCHours()+":"+dt.getMinutes()+":"+dt.getSeconds();

    var datetime = year+"/"+month+"/"+date;
    return datetime;

    // console.log(this.daterange.start);
  }

  set_filter_customer(event){

  }

  get_item(){
    // this.set_item(this.selected_item);
    
    this.Landa_.DataGet("/tes_2/get_item", {

    }).subscribe((res: any)=>{
      // console.log(res);
      this.data_item = res.detail;
      // console.log(this.data_item);
    })
  }

  get_cabang(){
    this.Landa_.DataGet("/tes_2/get_cabang", {
      
    }).subscribe((res: any)=>{
      // console.log(res);
      this.data_cabang = res.detail;
      console.log(this.data_cabang);
    })
  }

  get_gudang(dt_){
    this.Landa_.DataGet("/tes_2/get_gudang", {
      cabang: dt_
    }).subscribe((res: any)=>{
      // console.log(res);
      this.data_gudang = res.detail;
      console.log(this.data_gudang);
    })
  }

  set_item(event){
    this.f_item = event.target.value;
  }

  set_cabang(event){
    this.f_cabang = event.target.value;
    this.get_gudang(this.f_cabang);
    // console.log(this.f_cabang);
  }
  set_gudang(event){
    this.f_gudang = event.target.value;
  }

  // get all data

  get_all(){
    // console.log(this.selected_item.id);
    this.Landa_.DataPost("/olah_data/get_data_report", {
      item: this.selected_item.id,
      gudang: this.f_gudang,
      periode_awal: this.tgl_start,
      periode_akhir: this.tgl_end,
    }).subscribe((res: any)=>{
      // console.log(res);
      this.data_all   = res.data.data_master;
      this.saldo_awal = res.data.saldo_awal;
    })
  }

}
