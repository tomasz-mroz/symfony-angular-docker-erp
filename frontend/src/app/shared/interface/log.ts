// import { User } from './user';

export interface Log {
  log: LogEntry;
  diff: Array<SimpleDiff | ArrayDiff>;
}

export interface LogEntry {
  id: number;
  // user: User;
  createdAt: string;
  entity: string;
  entityId: string;
}

export interface SimpleDiff {
  previous?: string;
  new?: string;
  displayName: string;
}

export interface ArrayDiff {
  displayName: string;
  records: SimpleDiff[];
}

export type LogDiff = SimpleDiff | ArrayDiff;
