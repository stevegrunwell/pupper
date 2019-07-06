import factory from 'factoria';

/**
 * @see App\Http\Resources\Post
 */
factory.define('Post', faker => ({
    content: faker.lorem.text,
    createdAt: new Date,
}));

export default factory;
