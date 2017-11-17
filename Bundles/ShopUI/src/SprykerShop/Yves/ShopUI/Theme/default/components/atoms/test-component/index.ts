import './style';
import { register } from '../../../app';
export default register('test-component', () => import('./test-component'));
