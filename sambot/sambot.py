from redbot.core import commands
import aiohttp


class Samaritan(commands.Cog):
    """Random quote or rekoning from Samaritan"""

    async def red_delete_data_for_user(self, **kwargs):
        """ Nothing to delete """
        return

    def __init__(self, bot):
        self.bot = bot

    @commands.command()
    async def sam(self, ctx):
        """Shows the avatar emoji."""
        await ctx.send(f"<:samaritan:1078116149029519491>")

    @commands.command()
    async def rekon(self, ctx, query="", limit=3, shuffle=1):
        """Gets a random Sam rekon.

        **.rekon** - Get random rekoning
        **.rekon list** - Show link to rekon list
        **.rekon 25** - Show rekon by ID #
        **.rekon word** - Rekon search single word
        **.rekon "multiple words"** - Rekon search multiple words
        """
        try:
            async with aiohttp.request("GET", "https://sambot.frwd.app?q="+query+"&limit="+str(limit)+"&shuffle="+str(shuffle)+"&platform=discord", headers={"Accept": "text/plain"}) as r:
                if r.status != 200:
                    return await ctx.send("Oops! Cannot get a Sam rekon...")
                result = await r.text(encoding="UTF-8")
        except aiohttp.ClientConnectionError:
            return await ctx.send("Oops! Cannot get a Sam rekon...")

        await ctx.send(f"<:samaritan:1078116149029519491> {result}")
