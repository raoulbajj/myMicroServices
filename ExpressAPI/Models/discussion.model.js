const mongoose = require('mongoose');

const discussionSchema = new mongoose.Schema({
    users: {
        type: Array,
        required: true,
        validate: {
            validator: function (v) {
                return Array.isArray(v) && v.length >= 2;
            },
            message: 'A discussion must have at least 2 users'
        }
    },
    author: {
        type: String,
        required: true
    },
    title: {
        type: String,
        required: true,
        unique: true,
        validate: {
            validator: async function (v) {
                const discussion = await this.constructor.findOne({ title: v });
                if (discussion) {
                    if (this.id === discussion.id) {
                        return true;
                    }
                    return false;
                }
                return true;
            },
            message: props => `${props.value} already exists`
        }
    },
    messages: {
        type: [{
            id: Number,
            message: String,
            timestamp: Date,
            author: [Number, String]
        }],
        default: [
            {
                id: 0,
                message: "Welcome to the discussion ! \n Please start chatting",
                timestamp: Date.now(),
                author: 0
            }
        ]
    }
});

module.exports = mongoose.model('discussion', discussionSchema);
