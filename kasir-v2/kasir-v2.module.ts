import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { KasirV2RoutingModule } from './kasir-v2-routing.module';

import { NgbModule, NgbActiveModal, NgbDatepickerModule } from "@ng-bootstrap/ng-bootstrap";
import { Daterangepicker } from 'ng2-daterangepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { NgSelectModule } from '@ng-select/ng-select';

import  {MatCurrencyFormatModule} from 'mat-currency-format';
import { NewComponent } from './new/new.component';


@NgModule({
  declarations: [
    NewComponent
  ],
  imports: [
    CommonModule,
    KasirV2RoutingModule,
    NgbModule,
    Daterangepicker,
    NgbDatepickerModule,
    FormsModule,
    ReactiveFormsModule,
    NgSelectModule,
  ],
  providers: [NgbActiveModal,],

})
export class KasirV2Module { }
