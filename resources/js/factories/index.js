import factory from 'factoria';
import '@babel/polyfill';

/**
 * @see App\Http\Resources\Post
 */
factory.define('Post', faker => ({
    content: faker.lorem.text(),
    createdAt: new Date,
}));

export default factory;
