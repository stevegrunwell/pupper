import factory from 'factoria';
import '@babel/polyfill';

/**
 * @see App\Http\Resources\Post
 */
factory.define('Post', faker => ({
    id: faker.random.uuid(),
    content: faker.lorem.text(),
    createdAt: new Date,
    user: factory('User'),
}));

/**
 * @see App\Http\Resources\User
 */
factory.define('User', faker => ({
    id: faker.random.uuid(),
    username: faker.internet.userName(),
    displayName: faker.name.findName(),
}));

export default factory;
