export interface Address {
  uuid: string;
  id: number;
  street?: string;
  houseNumber?: string;
  apartmentNumber?: string;
  city?: string;
  postCity?: string;
  zipCode?: string;
  district?: string;
  commune?: string;
  state?: State;
}

export interface State {
  uuid: string;
  id: number;
  key: string;
  name: string;
}
