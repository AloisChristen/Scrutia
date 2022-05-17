import store from '@/store'
import { Dictionary } from 'vue-router/types/router';

const staticHeader:Dictionary<string> = {
  'Accept': "application/json",
  'Content-Type': 'application/json',
 // "User-Agent": "scrutia"
}

export class BaseService{


  makeBaseHeader(){
    const baseHeader = staticHeader;
    if(store.getters.isConnected){
        baseHeader['Authorization'] ='Bearer' + store.getters.token
    }
    return baseHeader
  }
  makeHeader(customHeaders:any){
    return {
      ...this.makeHeader,
      ...customHeaders
    }
  }
}
