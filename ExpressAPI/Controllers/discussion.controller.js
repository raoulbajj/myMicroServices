const discussionModel = require('../Models/discussion.model');

// ====================== CREATE A NEW DISCUSSION ======================
exports.createDiscussion = async (req, res) => {
    try {
        const users = req.body.users.sort((a, b) => a - b);

        const discussion = {
            title: req.body.title,
            users: users,
            author: req.body.author
        }

        const newDiscussion = new discussionModel(discussion);
        await newDiscussion.save();

        const discussionTitle = req.body.title;
        res.status(200).json({ message: `${discussionTitle} created successfully` });
    }
    catch (error) {
        res.status(500).json({ message: error.message });
    }
}

// ====================== GET ALL DISCUSSIONS ======================
exports.getAllDiscussions = async (req, res) => {
    try {
        const allDiscussions = await discussionModel.find();
        res.status(200).json(allDiscussions);
    }
    catch (err) {
        res.status(404).json('No discussions found \n' + err);
    }
}

// ====================== GET ALL DISCUSSIONS OF A SPECIFIC USER ===============
exports.getDiscussionsOfAUser = async (req, res) => {
    try {
        const userId = Number(req.params.id);
        const discussionsOfAUser = await discussionModel.find({ users: userId });

        if (discussionsOfAUser.length === 0)
            throw new Error(`No discussions found for user ${userId}`);
        res.status(200).json(discussionsOfAUser);
    }
    catch {
        return res.status(404).json('No discussions found');
    }
}

// ====================== GET A DISCUSSION BY ID ======================
exports.getDiscussionById = async (req, res) => {
    try {
        const discussionId = req.params.id;
        const discussion = await discussionModel.findOne({ _id: discussionId });

        if (!discussion)
            return res.status(404).json({ message: `No discussion found with id ${discussionId}` });

        res.status(200).json(discussion);
    }
    catch (error) {
        if (error.name === 'CastError') {
            return res.status(400).json({ message: 'Invalid ID format', error: error.message });
        }
        return res.status(500).json({ message: 'Error retrieving discussion', error: error.message });
    }
};

// ====================== PATCH A DISCUSSION BY ID ======================
exports.patchDiscussion = async (req, res) => {
    try {
        const discussionId = req.params.id;
        const discussion = await discussionModel.findOne({ _id: discussionId });

        if (!discussion)
            return res.status(404).json({ message: `No discussion found with id ${discussionId}` });

        const discussionToUpdate = {
            title: req.body.title,
        }

        await discussionModel.updateOne({ _id: discussionId }, discussionToUpdate);
        res.status(200).json({ message: `Discussion's title updated successfully` });
    }
    catch (error) {
        if (error.name === 'CastError') {
            return res.status(400).json({ message: 'Invalid ID format', error: error.message });
        }
        return res.status(500).json({ message: 'Error updating discussion', error: error.message });
    }
}

// ====================== GET ALL MESSAGES OF A DISCUSSION ======================
exports.getAllMsgOfADiscussion = async (req, res) => {
    try {
        const discussionTitle = req.body.title;
        const discussion = await discussionModel.findOne({ title: discussionTitle });

        if (!discussion)
            return res.status(404).json({ message: `No discussion found with title : ${discussionTitle}` });

        res.status(200).json(discussion.messages);
    }
    catch (error) {
        if (error.name === 'CastError') {
            return res.status(400).json({ error: error.message });
        }
        return res.status(500).json({ message: 'Error retrieving messages', error: error.message });
    }
}

exports.createMsgWithinDiscussion = async (req, res) => {
    try {
        const discussionTitle = req.body.title;
        const authorId = req.body.author;
        const discussion = await discussionModel.findOne({ title: discussionTitle });

        if (!discussion)
            return res.status(404).json({ message: `No discussion found with title : ${discussionTitle}` });

        const newMsg = {
            message: req.body.message,
            timestamp: Date.now(),
            author: authorId
        }

        await discussionModel.updateOne({ title: discussionTitle }, { $push: { messages: newMsg } });
        res.status(200).json({ message: `Message added successfully` });

    }
    catch (error) {
        if (error.name === 'CastError') {
            return res.status(400).json({ error: error.message });
        }
        return res.status(500).json({ message: 'Error adding message', error: error.message });
    }
}







// ====================== ADD A USER TO A DISCUSSION ======================
// A chaque ajout d'un user, vérifier que ce user n'est pas déjà dans la discussion

// ====================== DELETE A DISCUSSION BY ID ======================
// à chaque suppression de discussion, supprimer TOUS les messages associés à cette discussion ( faire communiquer les 2 api SLIM et Express)
// Penser à le faire une fois que j'aurai connecté les 2 API